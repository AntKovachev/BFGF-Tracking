export interface User {
  id: number;
  name: string;
  email: string;
}

export interface Transaction {
  id: number;
  amount: number;
  type: 'income' | 'expense';
  category?: string | null;
  description: string;
  date: string;
}

export interface Investment {
  id: number;
  name: string;
  amount_invested: number;
  current_value: number;
  profit_loss: number;
}

export interface DashboardSummaryResponse {
  summary: {
    total_income: number;
    total_expenses: number;
    balance: number;
  };
  goal: {
    goal_amount: number;
    progress_percentage: number;
    remaining_amount: number;
  };
  recent_transactions: Transaction[];
}
