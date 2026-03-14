import 'package:flutter/material.dart';
import '../models/job.dart';
import '../widgets/job_card.dart';

class JobListScreen extends StatelessWidget {
  const JobListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final jobs = [
      Job(1, 'Programador PHP', 'Maputo'),
      Job(2, 'Designer UI/UX', 'Beira'),
      Job(3, 'Gestor de Projetos', 'Nampula'),
    ];

    return Scaffold(
      appBar: AppBar(title: const Text('Vagas')),
      body: ListView.builder(
        itemCount: jobs.length,
        itemBuilder: (_, i) => JobCard(job: jobs[i]),
      ),
    );
  }
}
