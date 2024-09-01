import { api } from 'src/boot/axios'

const authService = {
  async login(form) {
    await api.get('/sanctum/csrf-cookie');
    const response = await api.post('/api/login', form);
    return response.data;
  },

  async register(form) {
    await api.get('/sanctum/csrf-cookie');
    const response = await api.post('/api/register', form);
    return response.data;
  },

  async getUser() {
    const response = await api.get('/api/user');
    return response.data;
  },
};

export default authService;
