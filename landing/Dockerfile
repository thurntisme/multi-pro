# Use Node.js as the base image
FROM node:18-alpine

# Set the working directory
WORKDIR /app

# Copy package.json and package-lock.json
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application
COPY . .

# Build the Next.js application
RUN npm run build

# Set the default port (change if needed)
ENV PORT=4000

# Expose the port
EXPOSE $PORT

# Use exec form to ensure the environment variable is passed correctly
CMD ["sh", "-c", "npm run start -- -p $PORT"]
