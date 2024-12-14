import redis
import csv

# Connect to Redis
r = redis.StrictRedis(host='localhost', port=6379, decode_responses=True)

# Read the worksOn.dat file and import data
with open('data/worksOn.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if len(row) == 3:
            ssn, project_number, hours = row

            # Construct the Redis key, e.g., works_on:123456789
            key = f"works_on:{ssn}"

            # Create a dictionary with project number and hours worked
            work_data = {
                "project_number": project_number,
                "hours": hours
            }

            # Use Redis RPUSH to add the work data to the list
            r.rpush(key, str(work_data))
            print(f"Inserted project {project_number} with {hours} hours for employee SSN: {ssn}")

print("Data import completed!")
