import redis
import csv

# Connect to Redis
r = redis.StrictRedis(host='localhost', port=6379, decode_responses=True)

# Read the project.dat file and import data
with open('data/project.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if len(row) == 4:
            project_name, project_number, location, department_number = row

            # Construct the Redis key, e.g., project:1
            key = f"project:{project_number}"

            # Store project details as a Redis hash
            project_data = {
                "project_name": project_name,
                "location": location,
                "department_number": department_number
            }

            # Use Redis HSET to store the project data
            r.hset(key, mapping=project_data)
            print(f"Inserted data for project number: {project_number}")

print("Data import completed!")
