import { api } from 'src/boot/axios'

const userService = {
  async getUser() {
    const response = await api.get('/api/user/me');
    return response;
  },

  async getVotersCount() {
    const response = await api.get('api/users/voters/count');
    return response;
  },

  async getUsersCountPerPurok() {
    const response = await api.get('api/users/per-purok/count');
    return response;
  }
};

export default userService;
