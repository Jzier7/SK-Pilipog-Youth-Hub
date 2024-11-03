import { api } from 'src/boot/axios'

const gameService = {
  async getPaginatedGames(params) {
    const response = await api.get('/api/game/retrieve/paginated', { params });
    return response;
  },

  async getAllGames(params) {
    const response = await api.get('/api/game/retrieve/all', { params });
    return response;
  },

  async storeGame(data) {
    const response = await api.post('/api/game/store', data );
    return response;
  },

  async updateGame(data) {
    const response = await api.patch('/api/game/update', data );
    return response;
  },

  async updateGameResult(data) {
    const response = await api.patch('/api/game/update/result', data );
    return response;
  },

  async deleteGame(data) {
    const response = await api.delete('/api/game/delete', { data });
    return response;
  },
};

export default gameService;
