from fastapi import FastAPI
from app.routers import ai_router

app = FastAPI()
app.include_router(ai_router.router)

@app.get("/")
def home():
    return {"message": "AI API is running!"}
