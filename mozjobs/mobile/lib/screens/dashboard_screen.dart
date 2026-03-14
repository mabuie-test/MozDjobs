import 'package:flutter/material.dart';
import 'chat_screen.dart';
import 'feed_screen.dart';
import 'job_list_screen.dart';
import 'notifications_screen.dart';
import 'reports_screen.dart';
import 'service_list_screen.dart';

class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('MozJobs Dashboard')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          const _StatsBanner(),
          const SizedBox(height: 14),
          _DashboardTile(
            icon: Icons.work_outline,
            title: 'Vagas',
            subtitle: 'Explore oportunidades e candidaturas',
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const JobListScreen())),
          ),
          _DashboardTile(
            icon: Icons.design_services_outlined,
            title: 'Serviços',
            subtitle: 'Contrate freelancers locais',
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ServiceListScreen())),
          ),
          _DashboardTile(
            icon: Icons.feed_outlined,
            title: 'Feed Social',
            subtitle: 'Stories, posts e interações da comunidade',
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const FeedScreen())),
          ),
          _DashboardTile(
            icon: Icons.chat_bubble_outline,
            title: 'Chat',
            subtitle: 'Converse sobre entregas e pedidos',
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ChatScreen())),
          ),
          _DashboardTile(
            icon: Icons.notifications_outlined,
            title: 'Notificações',
            subtitle: 'Acompanhe eventos da sua conta',
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const NotificationsScreen())),
          ),
          _DashboardTile(
            icon: Icons.analytics_outlined,
            title: 'Relatórios',
            subtitle: 'KPIs de performance da plataforma',
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ReportsScreen())),
          ),
        ],
      ),
    );
  }
}

class _StatsBanner extends StatelessWidget {
  const _StatsBanner();

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: const [
            _StatChip(label: 'Vagas', value: '28'),
            _StatChip(label: 'Serviços', value: '15'),
            _StatChip(label: 'Mensagens', value: '12'),
          ],
        ),
      ),
    );
  }
}

class _StatChip extends StatelessWidget {
  final String label;
  final String value;
  const _StatChip({required this.label, required this.value});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text(value, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800)),
        const SizedBox(height: 4),
        Text(label, style: const TextStyle(color: Color(0xFF64748B))),
      ],
    );
  }
}

class _DashboardTile extends StatelessWidget {
  final IconData icon;
  final String title;
  final String subtitle;
  final VoidCallback onTap;

  const _DashboardTile({required this.icon, required this.title, required this.subtitle, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: Card(
        child: ListTile(
          leading: CircleAvatar(
            backgroundColor: const Color(0xFFE8F0FE),
            child: Icon(icon, color: const Color(0xFF1877F2)),
          ),
          title: Text(title, style: const TextStyle(fontWeight: FontWeight.w700)),
          subtitle: Text(subtitle),
          trailing: const Icon(Icons.chevron_right),
          onTap: onTap,
        ),
      ),
    );
  }
}
