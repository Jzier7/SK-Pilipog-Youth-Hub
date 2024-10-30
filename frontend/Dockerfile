FROM node:22-alpine

# Set the working directory
WORKDIR /app

# Copy package files to the working directory
COPY package*.json ./

# Install Quasar CLI globally
RUN npm i -g @quasar/cli

# Copy the rest of your application code into the container
COPY . ./

# Install additional dependencies from package.json
RUN npm install

# Install dependencies including pinia-plugin-persistedstate
RUN npm install pinia-plugin-persistedstate

# Specify the command to run your application
CMD sh -c "quasar dev --host=0.0.0.0 --port 3000"

# Expose the port on which Quasar will run
EXPOSE 3000
