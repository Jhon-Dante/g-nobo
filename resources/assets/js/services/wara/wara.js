import axios from 'axios'
import configService from './config'

const waraService = axios.create({
    baseURL: configService.apiUrl
})

export default waraService
