import 'package:flutter/material.dart';
import '../models/service.dart';

class ServiceCard extends StatelessWidget {
  final ServiceModel service;
  const ServiceCard({super.key, required this.service});

  @override
  Widget build(BuildContext c) {
    return Card(
      child: ListTile(
        title: Text(service.title),
        subtitle: Text('${service.price.toStringAsFixed(0)} MZN'),
        trailing: const Icon(Icons.payments_outlined),
      ),
    );
  }
}
