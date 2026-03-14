import 'package:flutter/material.dart';
import '../models/job.dart';

class JobCard extends StatelessWidget {
  final Job job;
  const JobCard({super.key, required this.job});

  @override
  Widget build(BuildContext c) {
    return Card(
      child: ListTile(
        title: Text(job.title),
        subtitle: Text(job.location),
        trailing: const Icon(Icons.chevron_right),
      ),
    );
  }
}
