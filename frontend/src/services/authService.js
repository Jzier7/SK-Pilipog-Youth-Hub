import { api } from 'src/boot/axios'

const authService = {
  async login(form) {
    await api.get('/sanctum/csrf-cookie');
    const response = await api.post('/api/auth/login', form);
    return response.data;
  },

  async register(form) {
    await api.get('/sanctum/csrf-cookie');
    const response = await api.post('/api/auth/register', form);
    return response.data;
  },

  async logout() {
    const response = await api.post('/api/auth/logout');
    return response.data;
  },
};

export default authService;
