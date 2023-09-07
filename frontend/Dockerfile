FROM node:16

# Establecer el directorio de trabajo
WORKDIR /app

# Instalar las dependencias del proyecto
COPY package*.json ./
RUN npm install

# Copiar el resto de los archivos del proyecto
COPY . .

# Comando para iniciar la aplicación Vue
CMD ["npm", "run", "serve"]
