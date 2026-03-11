class ApiService {
  final String baseUrl = 'http://localhost:8080/api';

  Uri endpoint(String path) => Uri.parse('$baseUrl$path');
}
