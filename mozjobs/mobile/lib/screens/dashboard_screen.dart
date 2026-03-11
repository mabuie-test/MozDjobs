import 'package:flutter/material.dart';
import 'chat_screen.dart';
import 'job_list_screen.dart';
import 'service_list_screen.dart';
import 'notifications_screen.dart';

class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('MozJobs Dashboard')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Card(
            child: ListTile(
              title: const Text('Vagas'),
              subtitle: const Text('Explore oportunidades e candidaturas'),
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const JobListScreen())),
            ),
          ),
          Card(
            child: ListTile(
              title: const Text('Serviços'),
              subtitle: const Text('Contrate freelancers locais'),
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ServiceListScreen())),
            ),
          ),
          Card(
            child: ListTile(
              title: const Text('Chat'),
              subtitle: const Text('Converse sobre entregas e pedidos'),
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ChatScreen())),
            ),
          ),
          Card(
            child: ListTile(
              title: const Text('Notificações'),
              subtitle: const Text('Acompanhe eventos da sua conta'),
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const NotificationsScreen())),
            ),
          ),
        ],
      ),
    );
  }
}
