import 'package:flutter/material.dart';
import '../services/auth_service.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _email = TextEditingController();
  final _password = TextEditingController();
  final _auth = AuthService();
  String _feedback = '';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: SizedBox(
          width: 360,
          child: Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(mainAxisSize: MainAxisSize.min, children: [
                const Text('MozJobs Login', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                const SizedBox(height: 12),
                TextField(controller: _email, decoration: const InputDecoration(labelText: 'Email')),
                TextField(controller: _password, decoration: const InputDecoration(labelText: 'Senha'), obscureText: true),
                const SizedBox(height: 12),
                FilledButton(
                  onPressed: () async {
                    final ok = await _auth.login(_email.text, _password.text);
                    if (!mounted) return;
                    if (ok) {
                      Navigator.pushReplacementNamed(context, '/dashboard');
                    } else {
                      setState(() => _feedback = 'Credenciais inválidas');
                    }
                  },
                  child: const Text('Entrar'),
                ),
                TextButton(onPressed: () => Navigator.pushNamed(context, '/register'), child: const Text('Criar conta')),
                if (_feedback.isNotEmpty) Text(_feedback),
              ]),
            ),
          ),
        ),
      ),
    );
  }
}
