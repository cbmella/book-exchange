# Usar una imagen base de Node.js
FROM node:16

# Establecer el directorio de trabajo
WORKDIR /app

# Instalar las dependencias del proyecto
COPY package*.json ./
RUN npm install

# Copiar el resto de los archivos del proyecto
COPY . .

# Construir la aplicación Next.js
RUN npm run build

# Comando para iniciar la aplicación Next.js
CMD ["npm", "run", "dev"]
