FROM node:20

WORKDIR /usr/src/app

RUN apt-get update && apt-get install -y python3 make g++

RUN npm install -g pnpm

# Copia archivos de dependencias primero
COPY package.json pnpm-lock.yaml* ./

# Instala TODAS las dependencias, incluidas las de desarrollo
RUN pnpm install --store-dir .pnpm-store

# Copia el resto del proyecto
COPY . .

EXPOSE 5173

CMD ["pnpm", "dev"]
