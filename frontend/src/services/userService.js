import { api } from 'src/boot/axios'

const userService = {
  async getUser() {
    const response = await api.get('/api/user/me');
    return response;
  },

  async getAllUser(params) {
    const response = await api.get('/api/users/retrieve/all/users', { params });
    return response;
  },

  async getAllAdmin(params) {
    const response = await api.get('/api/users/retrieve/all/admins', { params });
    return response;
  },

  async storeAdmin(data) {
    const response = await api.post('/api/users/store/admin', data);
    return response;
  },

  async updateUser(data) {
    const response = await api.patch('/api/users/update/data', data);
    return response;
  },

  async updateUserStatus(data) {
    const response = await api.patch('/api/users/update/status', data);
    return response;
  },

  async deleteUser(data) {
    const response = await api.delete('/api/users/delete', { data });
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
