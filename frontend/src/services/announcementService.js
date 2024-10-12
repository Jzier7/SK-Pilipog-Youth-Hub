import { api } from 'src/boot/axios'

const announcementService = {
  async getAllAnnouncement(params) {
    const response = await api.get('/api/announcement/retrieve/all', { params });
    return response;
  },

  async storeAnnouncement(data) {
    const response = await api.post('/api/announcement/store', data);
    return response;
  },

  async updateAnnouncement(data) {
    const response = await api.patch('/api/announcement/update', data);
    return response;
  },

  async deleteAnnouncement(data) {
    const response = await api.delete('/api/announcement/delete', { data });
    return response;
  },
};

export default announcementService;
