import axios from 'axios';


class Api {
  constructor() {
    this.api = axios.create({
      baseURL: 'http://localhost:8000/api',
      headers: {
        'Content-Type': 'application/json',
      },
    });

    this.api.interceptors.request.use(
      (config) => {
        const token = localStorage.getItem('user_token');
        if (token) {
          config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
      },
      (error) => Promise.reject(error)
    );

    this.api.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response && error.response.status === 401) {
          localStorage.removeItem('user_token');
          delete this.api.defaults.headers.common['Authorization'];
          window.location.href = '/login';
        }
        return Promise.reject(error);
      }
    );
  }

  async get(url, params = {}) {
    try {
      const response = await this.api.get(url, { params });
      return response.data;
    } catch (error) {
      console.error('Erro na requisição GET:', error);
      throw error;
    }
  }
  async post(url, data) {
    try {
      const response = await this.api.post(url, JSON.stringify(data));
      return response.data;
    } catch (error) {
      console.error('Erro na requisição POST:', error);
      throw error;
    }
  }

  async put(url, data) {
    try {
      const response = await this.api.put(url, data);
      return response.data;
    } catch (error) {
      console.error('Erro na requisição PUT:', error);
      throw error;
    }
  }

  async delete(url) {
    try {
      const response = await this.api.delete(url);
      return response.data;
    } catch (error) {
      console.error('Erro na requisição DELETE:', error);
      throw error;
    }
  }
}
const api = new Api();

export default api;