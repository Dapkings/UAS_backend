import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { Transport, MicroserviceOptions } from '@nestjs/microservices';

async function bootstrap() {
  const app = await NestFactory.create(AppModule);

  app.enableCors({
    origin: true, // Izinkan semua sumber (Frontend)
    methods: 'GET,HEAD,PUT,PATCH,POST,DELETE',
    credentials: true,
  });

  app.connectMicroservice<MicroserviceOptions>({
    transport: Transport.RMQ,
    options: {
      urls: ['amqp://localhost:5672'], 
      queue: 'main_queue',             
      queueOptions: {
        durable: false,
      },
    },
  });

  await app.startAllMicroservices();
  app.enableCors();
  await app.listen(3002);
  console.log('Order Service is running on port 3002');
}
bootstrap();