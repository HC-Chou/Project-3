import redis
import csv

# Connect to Redis
r = redis.StrictRedis(host='localhost', port=6379, decode_responses=True)

# Read the .dat file and import data
with open('data/dependent.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if len(row) == 5:
            ssn, name, gender, birthdate, relationship = row

            # Construct the Redis key, e.g., family:333445555
            key = f"family:{ssn}"

            # Store each family member as a Redis list
            member_data = {
                "name": name,
                "gender": gender,
                "birthdate": birthdate,
                "relationship": relationship
            }

            # Use Redis RPUSH to add the data to the list
            r.rpush(key, str(member_data))
            print(f"Inserted data for SSN: {ssn}")

print("Data import completed!")
