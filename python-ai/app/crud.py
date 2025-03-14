import uuid
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import Dict, List

app = FastAPI()

# In-memory database
db = {}

# Pydantic model
class Item(BaseModel):
    id: str = None
    name: str
    description: str
    extra_data: Dict[str, str] = {}  # Dynamic field

# CRUD Operations
@app.post("/", response_model=Item)
def create_item(item: Item):
    item.id = str(uuid.uuid4())  # Generate unique ID
    db[item.id] = item
    return item

@app.get("/", response_model=List[Item])
def get_items():
    return list(db.values())

@app.get("/{item_id}", response_model=Item)
def get_item(item_id: str):
    if item_id not in db:
        raise HTTPException(status_code=404, detail="Item not found")
    return db[item_id]

@app.put("/{item_id}", response_model=Item)
def update_item(item_id: str, item: Item):
    if item_id not in db:
        raise HTTPException(status_code=404, detail="Item not found")
    db[item_id] = item
    return item

@app.delete("/{item_id}")
def delete_item(item_id: str):
    if item_id not in db:
        raise HTTPException(status_code=404, detail="Item not found")
    del db[item_id]
    return {"message": "Item deleted"}
