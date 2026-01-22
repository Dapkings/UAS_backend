import { Body, Controller, Get, Post } from '@nestjs/common';
import { EventPattern, Payload } from '@nestjs/microservices';

// 1. UBAH INI: Tambahkan 'orders' agar URL menjadi http://localhost:3002/orders
@Controller('orders') 
export class AppController {

  // 2. TAMBAHKAN INI: Endpoint untuk tombol "Pesan" dari Frontend
  @Post('create')
  createOrder(@Body() data: any) {
    console.log(' Order masuk dari Frontend:', data);
    
    return {
      message: 'Order berhasil dibuat',
      orderId: Math.floor(Math.random() * 10000) + 1,
      productId: data.productId,
      status: 'confirmed'
    };
  }

  // --- Kode lama kamu (tetap disimpan) ---
  @Get()
  getHello(): string {
    return 'Order Service is Ready!';
  }

  @EventPattern('user.created')
  async handleUserCreated(@Payload() data: any) {
    console.log('================================');
    console.log(' EVENT DITERIMA DARI RABBITMQ!');
    console.log('Data User Baru:', data);
    console.log('================================');
  }
}