import clickhouse_connect

class AIRepository:
    def __init__(self):
        self.client = clickhouse_connect.get_client(host="clickhouse", port=8123)

    def save_query(self, keyword: str, user: str):
        self.client.execute(
            "INSERT INTO ai_queries (keyword, user) VALUES (%s, %s)",
            (keyword, user),
        )
