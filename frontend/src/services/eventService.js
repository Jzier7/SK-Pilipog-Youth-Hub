import { api } from 'src/boot/axios'

const eventService = {
  async getEvents(params) {
    const response = await api.get('/api/event/retrieve', { params });
    return response;
  },

  async storeEvent(data) {
    const response = await api.post('/api/event/store', data );
    return response;
  },

  async updateEvent(data) {
    const response = await api.patch('/api/event/update', data );
    return response;
  },

  async deleteEvent(data) {
    const response = await api.delete('/api/event/delete', { data });
    return response;
  },
};

export default eventService;
