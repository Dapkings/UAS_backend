FROM node:18-alpine

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .

RUN npm run build

# Buka port 3002 (Sesuai Soal)
EXPOSE 3002

CMD ["npm", "run", "start:prod"]