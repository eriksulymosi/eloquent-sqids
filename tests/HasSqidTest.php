<?php

namespace ErikSulymosi\EloquentSqids\Tests;

use ErikSulymosi\EloquentSqids\Facades\Sqids;
use ErikSulymosi\EloquentSqids\Tests\Models\Item;
use ErikSulymosi\EloquentSqids\Tests\Models\ItemWithCustomSqidsConnection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('can encode the model id to its sqid', function ()
{
	$item = Item::factory()->create();

	$sqid = Sqids::encode([$item->getKey()]);

	$this->assertEquals($sqid, $item->sqid());
	$this->assertEquals($sqid, $item->sqid);
});

it('can append its sqid to its serialized version', function ()
{
	$item = Item::factory()->create();

	$sqid = Sqids::encode([$item->getKey()]);

	$serialized = json_decode($item->append('sqid')->toJson());

	$this->assertEquals($sqid, $serialized->sqid);
});

it('can enceode an arbitrary id to its sqid', function ()
{
	$item = new Item();

	$sqid = Sqids::encode([123]);

	$this->assertEquals($sqid, $item->idToSqid(123));
});	

it('can find models by sqid', function ()
{
	$item = Item::factory()->create();
	
	$sqid = Sqids::encode([$item->getKey()]);

	$found = Item::findBySqid($sqid);

	$this->assertNotNull($found);
	$this->assertEquals($item->id, $found->id);
});

it('returns null when cannot find a model with a sqid', function ()
{
	$sqid = Sqids::encode([1]);

	$found = Item::findBySqid($sqid);

	$this->assertNull($found);
});

it('can find a model by its sqid or fail', function ()
{
	$item = Item::factory()->create();

	$sqid = Sqids::encode([$item->getKey()]);

	$found = Item::findBySqidOrFail($sqid);

	$this->assertEquals($item->id, $found->id);

	$item->delete();

	$this->expectException(ModelNotFoundException::class);

	Item::findBySqidOrFail($sqid);
});

it('can decode a sqid to the id', function ()
{
	$item = Item::factory()->create();

	$sqid = Sqids::encode([$item->getKey()]);

	$id = (new Item)->sqidToId($sqid);

	$this->assertEquals($item->id, $id);
});

it('can handle invalid sqids properly', function ()
{
	$this->assertNull((new Item)->sqidToId('not a sqid'));

	$this->assertNull(Item::findBySqid('not a sqid'));

	$this->expectException(ModelNotFoundException::class);
	Item::findBySqidOrFail('not a sqid');
});

it('can handle custom sqids connection for model', function ()
{
	$this->assertEquals((new ItemWithCustomSqidsConnection)->getSqidsConnection(), 'custom');

    $this->assertEquals('bdcdecabad', (new ItemWithCustomSqidsConnection)->idToSqid(1));
});

it('allows for strict sqid to ID conversion', function ()
{
    $item = ItemWithCustomSqidsConnection::factory()->create();

    $this->assertEquals('bdcdecabad', $item->sqid());

    $this->assertTrue(ItemwithCustomSqidsConnection::findBySqid('bdcdecabad')->is($item));
    $this->assertTrue(ItemwithCustomSqidsConnection::findBySqid('bd')->is($item));

    ItemWithCustomSqidsConnection::setStrictSqids();

    $this->assertTrue(ItemwithCustomSqidsConnection::findBySqid('bdcdecabad')->is($item));
    $this->assertNull(ItemwithCustomSqidsConnection::findBySqid('bd'));

});
