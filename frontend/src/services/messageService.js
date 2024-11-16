import { api } from 'src/boot/axios'

const messageService = {
  async retrieveUser(params) {
    const response = await api.get('/api/message/retrieve/user');
    return response;
  },

  async retrieveAdmin(params) {
    const response = await api.get('/api/message/retrieve/admin', { params });
    return response;
  },

  async send(data) {
    const response = await api.post('/api/message/send', data );
    return response;
  },

  async read(data) {
    const response = await api.patch('/api/message/read', data );
    return response;
  },

};

export default messageService;
