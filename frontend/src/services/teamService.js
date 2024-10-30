import { api } from 'src/boot/axios'

const teamService = {
  async getTeams(params) {
    const response = await api.get('/api/team/retrieve', { params });
    return response;
  },

  async storeTeam(data) {
    const response = await api.post('/api/team/store', data );
    return response;
  },

  async updateTeam(data) {
    const response = await api.patch('/api/team/update', data );
    return response;
  },

  async deleteTeam(data) {
    const response = await api.delete('/api/team/delete', { data });
    return response;
  },
};

export default teamService;
