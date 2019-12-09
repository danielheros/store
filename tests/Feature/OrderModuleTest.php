<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class OrderModuleTest extends TestCase
{

  use RefreshDatabase,WithFaker;

    /**
     * @test
     */
    public function it_loads_the_orders_list_page()
    {
        $response = $this->get('/orders');

        $response->assertStatus(200);
        $response->assertSee('Pedidos');
    }

    /**
     * @test
     */
    public function it_loads_the_orders_list_admin_page()
    {
        $response = $this->get('/orders-admin');

        $response->assertStatus(200);
        $response->assertSee('Pedidos');
    }

    /**
     * @test
     */
    public function it_loads_the_order_create_page()
    {
        $response = $this->get('/orders/create');

        $response->assertStatus(200);
        $response->assertSee('Realizar pedido');
    }


    /**
     * @test
     */
    public function user_can_create_order()
    {

        $response = $this->postJson(route('orders.store'), [
          'customer_name' => $this->faker->name,
          'customer_email' => $this->faker->email,
          'customer_mobile' => $this->faker->phoneNumber,
          'status' => 'CREATED',
          'created_at' => $this->faker->dateTimeThisMonth($max = 'now', $timezone = null),
          'updated_at' => $this->faker->dateTimeThisMonth($max = 'now', $timezone = null),
        ]);


        $response->assertStatus(Response::HTTP_FOUND);

    }


    /**
     * @test
     */
    public function it_loads_the_order_resume_page()
    {
        $response = $this->get('/orders/1')
                          ->assertSee('Resumen del pedido');


    }

}
