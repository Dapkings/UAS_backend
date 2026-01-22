import { Injectable, Inject } from '@nestjs/common';
import { ClientProxy } from '@nestjs/microservices';
import { RegisterDto } from '../register.dto';

@Injectable()
export class AuthService {
  constructor(@Inject('RABBITMQ_SERVICE') private readonly client: ClientProxy) {}

  async register(dto: RegisterDto) {
    const userId = Math.floor(Math.random() * 10000); 

    const userData = {
      userId: userId,
      email: dto.email,
    };

    this.client.emit('user.created', userData);
    
    return {
      message: 'User registered successfully',
      data: userData,
    };
  }
}