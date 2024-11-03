import { api } from 'src/boot/axios'

const announcementService = {
  async getPaginatedAnnouncement(params) {
    const response = await api.get('/api/announcement/retrieve/paginated', { params });
    return response;
  },

  async getLatestAnnouncement() {
    const response = await api.get('/api/announcement/retrieve/latest');
    return response;
  },

  async storeAnnouncement(data) {
    const response = await api.post('/api/announcement/store', data);
    return response;
  },

  async updateAnnouncement(data) {
    const response = await api.post('/api/announcement/update', data);
    return response;
  },

  async deleteAnnouncement(data) {
    const response = await api.delete('/api/announcement/delete', { data });
    return response;
  },
};

export default announcementService;
