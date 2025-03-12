from pydantic import BaseModel

class AIRequest(BaseModel):
    keyword: str

class AIResponse(BaseModel):
    message: str
    user: str
