import 'api_service.dart';

class AuthService {
  final ApiService _api = ApiService();

  Future<bool> login(String email, String password) async {
    _api.endpoint('/auth/login');
    return email.isNotEmpty && password.isNotEmpty;
  }
}
