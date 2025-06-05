import axios from 'axios'

const instance = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Add a response interceptor
instance.interceptors.response.use(
  response => {
    return response.data
  },
  error => {
    if (error.response) {
      // The request was made and the server responded with a status code
      // that falls out of the range of 2xx
      return Promise.reject(error.response.data)
    } else if (error.request) {
      // The request was made but no response was received
      return Promise.reject({ message: 'No response from server' })
    } else {
      // Something happened in setting up the request that triggered an Error
      return Promise.reject({ message: error.message })
    }
  }
)

export default instance 