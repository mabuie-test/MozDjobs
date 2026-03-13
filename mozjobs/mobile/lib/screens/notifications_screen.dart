import 'package:flutter/material.dart';

class NotificationsScreen extends StatelessWidget {
  const NotificationsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final notifications = const [
      'Nova candidatura recebida',
      'Pagamento em escrow confirmado',
      'Disputa resolvida pela administração',
    ];

    return Scaffold(
      appBar: AppBar(title: const Text('Notificações')),
      body: ListView(
        children: notifications
            .map((n) => Card(child: ListTile(leading: const Icon(Icons.notifications), title: Text(n))))
            .toList(),
      ),
    );
  }
}
