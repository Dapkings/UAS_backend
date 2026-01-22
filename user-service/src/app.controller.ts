import { Body, Controller, Inject, Post } from '@nestjs/common';
import { ClientProxy } from '@nestjs/microservices';
import { RegisterDto } from './register.dto';

@Controller('auth')
export class AppController {
  constructor(@Inject('USER_SERVICE_HUB') private readonly client: ClientProxy) {}

  @Post('register')
  async register(@Body() data: RegisterDto) {
    const userId = Math.floor(Math.random() * 1000);
    
    const userPayload = {
      userId: userId,
      email: data.email,
    };

    this.client.emit('user.created', userPayload);

    return {
      message: 'User registered successfully',
      data: userPayload,
    };
  }
  
 @Post('login')
  async login(@Body() data: any) {
    return {
      message: 'Login berhasil',
      token: 'dummy-token-123',
      user: { id: 1, email: data.email }
    };
  }
}