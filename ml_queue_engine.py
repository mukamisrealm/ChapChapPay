import mysql.connector
import json
import random

try:
    db = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="paychap"
    )
    cursor = db.cursor(dictionary=True)

    # Fetch active members along with payout status and contribution records
    cursor.execute("""
        SELECT u.id, u.name, 
               COALESCE(SUM(c.amount), 0) AS total_paid,
               COUNT(f.id) AS fines_count,
               COALESCE(pq.status, 'pending') AS payout_status
        FROM users u
        LEFT JOIN contributions c ON u.id = c.user_id
        LEFT JOIN fines f ON u.id = f.user_id
        LEFT JOIN payout_queue pq ON u.id = pq.user_id
        WHERE u.role = 'member'
        GROUP BY u.id
    """)
    members = cursor.fetchall()

    for m in members:
        # Base reliability score calculation
        base_score = float(m['total_paid']) - (float(m['fines_count']) * 300)
        
        # Penalize members who have already received a payout in the current round
        payout_penalty = 5000 if m['payout_status'] == 'completed' else 0
        
        # Add a dynamic variance component (prevents rigid deterministic ties)
        stochastic_factor = random.uniform(0.9, 1.1)
        
        m['final_score'] = (base_score - payout_penalty) * stochastic_factor

    # Sort dynamically based on adjusted stochastic scores
    members.sort(key=lambda x: x['final_score'], reverse=True)

    sorted_ids = [m['id'] for m in members]
    print(json.dumps(sorted_ids))

except Exception as e:
    print(json.dumps([]))