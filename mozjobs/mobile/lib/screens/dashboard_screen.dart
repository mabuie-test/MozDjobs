import 'package:flutter/material.dart';

class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('MozJobs Dashboard')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: const [
          Card(child: ListTile(title: Text('Vagas'), subtitle: Text('Explore oportunidades e candidaturas'))),
          Card(child: ListTile(title: Text('Serviços'), subtitle: Text('Contrate freelancers locais'))),
          Card(child: ListTile(title: Text('Chat'), subtitle: Text('Converse sobre entregas e pedidos'))),
        ],
      ),
    );
  }
}
