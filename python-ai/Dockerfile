# Use a minimal Python image
FROM python:3.9-slim

# Set working directory
WORKDIR /app

# Copy the application files
COPY . /app

# Install dependencies
RUN pip install --no-cache-dir fastapi uvicorn

# Expose the port
EXPOSE 5001

# Start the FastAPI app
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "5001"]
