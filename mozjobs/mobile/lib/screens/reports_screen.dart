import 'package:flutter/material.dart';

class ReportsScreen extends StatelessWidget {
  const ReportsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    const data = {
      'GMV': '120,000 MZN',
      'Take rate (10%)': '12,000 MZN',
      'Orders': '74',
      'Average rating': '4.6'
    };

    return Scaffold(
      appBar: AppBar(title: const Text('Relatórios')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: data.entries
            .map((e) => Card(child: ListTile(title: Text(e.key), trailing: Text(e.value))))
            .toList(),
      ),
    );
  }
}
