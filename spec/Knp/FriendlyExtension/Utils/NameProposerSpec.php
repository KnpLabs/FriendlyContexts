<?php

namespace spec\Knp\FriendlyExtension\Utils;

use Knp\FriendlyExtension\Utils\TextFormater;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameProposerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new TextFormater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Utils\NameProposer');
    }

    function it_build_proposals()
    {
        $this->buildProposals('Product')->shouldReturn([
            'PRODUCT',
            'Product',
            'product',
        ]);
    }

    function it_build_proposals_with_plurials()
    {
        $this->buildProposals('Product', true)->shouldReturn([
            'PRODUCT',
            'PRODUCTS',
            'Product',
            'Products',
            'product',
            'products',
        ]);
    }

    function it_build_proposals_from_complex_string()
    {
        $this->buildProposals('Order item', true)->shouldReturn([
            "ORDER ITEM",
            "ORDER ITEMS",
            "ORDERITEM",
            "ORDERITEMS",
            "ORDER_ITEM",
            "ORDER_ITEMS",
            "Order item",
            "Order items",
            "order item",
            "order items",
            "orderItem",
            "orderItems",
            "order_item",
            "order_items",
            "orderitem",
            "orderitems",
        ]);
    }

    function it_should_detect_matches()
    {
        $this->match('product', 'Product')->shouldReturn(true);
        $this->match('product item', 'ProductItem')->shouldReturn(true);
        $this->match('product items', 'ProductItem', true)->shouldReturn(true);
    }
}
