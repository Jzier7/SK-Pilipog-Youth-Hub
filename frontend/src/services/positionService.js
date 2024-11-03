import { api } from 'src/boot/axios'

const positionService = {
  async getPaginatedPositions(params) {
    const response = await api.get('/api/position/retrieve/paginated', { params });
    return response;
  },

  async getAllPositions() {
    const response = await api.get('/api/position/retrieve/all');
    return response;
  },

  async storePosition(data) {
    const response = await api.post('/api/position/store', data );
    return response;
  },

  async updatePosition(data) {
    const response = await api.patch('/api/position/update', data );
    return response;
  },

  async deletePosition(data) {
    const response = await api.delete('/api/position/delete', { data });
    return response;
  },
};

export default positionService;
