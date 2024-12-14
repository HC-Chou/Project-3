import redis
import csv

# Connect to Redis
r = redis.StrictRedis(host='localhost', port=6379, decode_responses=True)

# Read the .dat file and import data
with open('data/dloc.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if len(row) == 2:
            department_id, location = row

            # Construct the Redis key, e.g., department_location:5
            key = f"department_location:{department_id}"

            # Use Redis RPUSH to add the location to the list
            r.rpush(key, location)
            print(f"Inserted location '{location}' for Department ID: {department_id}")

print("Data import completed!")
