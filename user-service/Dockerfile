# Gunakan image Node.js versi ringan
FROM node:18-alpine

# Tentukan folder kerja di dalam container
WORKDIR /app

# Copy file package.json dan package-lock.json
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy semua kode sumber project ke dalam container
COPY . .

# Build aplikasi NestJS
RUN npm run build

# Buka port 3001 (Sesuai Soal)
EXPOSE 3001

# Perintah untuk menjalankan aplikasi
CMD ["npm", "run", "start:prod"]