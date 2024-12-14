import redis
import csv

# Connect to Redis
r = redis.StrictRedis(host='localhost', port=6379, decode_responses=True)

# Read the employee.dat file and import data
with open('data/employee.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if len(row) == 10:
            first_name, middle_initial, last_name, ssn, birthdate, address, gender, salary, supervisor_ssn, department_number = row

            # Construct the Redis key, e.g., employee:222222202
            key = f"employee:{ssn}"

            # Store employee details as a Redis hash
            employee_data = {
                "first_name": first_name,
                "middle_initial": middle_initial,
                "last_name": last_name,
                "birthdate": birthdate,
                "address": address,
                "gender": gender,
                "salary": salary,
                "supervisor_ssn": supervisor_ssn,
                "department_number": department_number
            }

            # Use Redis HSET to store the employee data
            r.hset(key, mapping=employee_data)
            print(f"Inserted data for employee SSN: {ssn}")

print("Data import completed!")
