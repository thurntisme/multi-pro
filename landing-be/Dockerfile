# Use the official Node.js image
FROM node:18-alpine

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package*.json ./
RUN npm install

# Copy the rest of the application
COPY . .

# Build the NestJS application
RUN npm run build

# Set environment variable for custom port
ENV PORT=5050

# Expose the new port
EXPOSE $PORT

# Start the NestJS application
CMD ["npm", "run", "start"]
