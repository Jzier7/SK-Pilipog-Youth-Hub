import { api } from 'src/boot/axios'

const teamLikeService = {
  async like(data) {
    const response = await api.post('/api/like', data );
    return response;
  },
};

export default teamLikeService;
