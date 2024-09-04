import { api } from 'src/boot/axios'

const userService = {
  async getUser() {
    const response = await api.get('/api/user');
    return response.data;
  },
};

export default userService;
