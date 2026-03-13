import 'package:flutter/material.dart';

class FeedScreen extends StatefulWidget {
  const FeedScreen({super.key});

  @override
  State<FeedScreen> createState() => _FeedScreenState();
}

class _FeedScreenState extends State<FeedScreen> {
  final List<Map<String, dynamic>> _stories = const [
    {'name': 'Ana', 'text': 'Nova vaga de PHP', 'color': Colors.blue},
    {'name': 'Paulo', 'text': 'Freela em design', 'color': Colors.deepPurple},
    {'name': 'Lina', 'text': 'Dica de carreira', 'color': Colors.teal},
  ];

  final List<Map<String, dynamic>> _posts = [
    {'author': 'Ana', 'content': 'Estamos a contratar dev #PHP #Remote', 'likes': 4, 'comments': 2},
    {'author': 'Paulo', 'content': 'Novo serviço de branding disponível #Design', 'likes': 8, 'comments': 1},
  ];

  void _like(int index) {
    setState(() {
      _posts[index]['likes'] = (_posts[index]['likes'] as int) + 1;
    });
  }

  void _comment(int index) {
    setState(() {
      _posts[index]['comments'] = (_posts[index]['comments'] as int) + 1;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Feed Social')),
      body: ListView(
        padding: const EdgeInsets.all(12),
        children: [
          const Text('Stories', style: TextStyle(fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          SizedBox(
            height: 96,
            child: ListView.separated(
              scrollDirection: Axis.horizontal,
              itemCount: _stories.length,
              separatorBuilder: (_, __) => const SizedBox(width: 8),
              itemBuilder: (_, i) {
                final s = _stories[i];
                return Container(
                  width: 140,
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: s['color'] as Color,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(s['name'] as String, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 6),
                      Text(s['text'] as String, style: const TextStyle(color: Colors.white)),
                    ],
                  ),
                );
              },
            ),
          ),
          const SizedBox(height: 16),
          const Text('Timeline', style: TextStyle(fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          ...List.generate(_posts.length, (i) {
            final post = _posts[i];
            return Card(
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(post['author'] as String, style: const TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 6),
                    Text(post['content'] as String),
                    const SizedBox(height: 10),
                    Row(
                      children: [
                        TextButton.icon(
                          onPressed: () => _like(i),
                          icon: const Icon(Icons.thumb_up_alt_outlined),
                          label: Text('Like (${post['likes']})'),
                        ),
                        TextButton.icon(
                          onPressed: () => _comment(i),
                          icon: const Icon(Icons.comment_outlined),
                          label: Text('Comentário (${post['comments']})'),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            );
          }),
        ],
      ),
    );
  }
}
