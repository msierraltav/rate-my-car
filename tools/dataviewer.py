import pandas as pd
import argparse
import os
from typing import Optional, Dict, List

def load_csv(file_path: str) -> pd.DataFrame:
  """
  Load a CSV file into a pandas DataFrame.
  
  Args:
    file_path: Path to the CSV file
    
  Returns:
    DataFrame containing the CSV data
  """
  if not os.path.exists(file_path):
    raise FileNotFoundError(f"File not found: {file_path}")
  
  return pd.read_csv(file_path, sep="|")


def get_unique_values(df: pd.DataFrame) -> Dict[str, List]:
  """
  Get unique values for each column in the DataFrame.
  
  Args:
    df: Input DataFrame
    
  Returns:
    Dictionary mapping column names to lists of unique values
  """
  unique_values = {}
  for column in df.columns:
    unique_values[column] = df[column].unique().tolist()
  return unique_values


def print_unique_values(df: pd.DataFrame, column: Optional[str] = None):
  """
  Print unique values for a specific column or all columns.
  
  Args:
    df: Input DataFrame
    column: Column name to print unique values for. If None, print for all columns.
  """
  if column:
    if column not in df.columns:
      print(f"Column '{column}' not found in the DataFrame")
      return
    
    values = df[column].unique()
    print(f"Column: {column}")
    print(f"Number of unique values: {len(values)}")
    print("Unique values:", values)
  else:
    for col in df.columns:
      if col == 'vin':
        print(f"Unique values for VIN : {len(df[col].unique())}")
      else :
        values = df[col].unique()
        print(f"Column: {col}")
        print(f"Number of unique values: {len(values)}")
        print("Unique values:", values[:100] if len(values) > 100 else values)
        # print("Unique values:", values)
        print("-----")

def analyze_vehicle_hierarchy(df: pd.DataFrame) -> Dict:
  """
  Analyze the hierarchical relationship between make, model, and trim.
  
  Args:
    df: Input DataFrame containing vehicle data
    
  Returns:
    Dictionary with makes as keys, models as sub-keys, and trims as values
  """
  if not all(col in df.columns for col in ['make', 'model', 'trim']):
    print("Error: DataFrame must contain 'make', 'model', and 'trim' columns")
    return {}
  
  hierarchy = {}
  
  # Group by make and model to build the hierarchy
  for make in df['make'].unique():
    hierarchy[make] = {}
    make_data = df[df['make'] == make]
    
    for model in make_data['model'].unique():
      model_data = make_data[make_data['model'] == model]
      trims = model_data['trim'].unique().tolist()
      hierarchy[make][model] = trims
  
  return hierarchy


def print_vehicle_hierarchy(hierarchy: Dict):
  """
  Print the hierarchical relationship between make, model, and trim.
  
  Args:
    hierarchy: Dictionary with the vehicle hierarchy
  """
  print("\nVehicle Hierarchy:")
  print("=================")
  
  for make, models in hierarchy.items():
    print(f"Make: {make}")
    for model, trims in models.items():
      print(f"  Model: {model}")
      print(f"    Trims: {trims[:5]}{'...' if len(trims) > 5 else ''}")
      print(f"    Total trims: {len(trims)}")
    print("-----")


def main():
  parser = argparse.ArgumentParser(description="CSV Data Viewer")
  parser.add_argument("file", help="Path to CSV file")
  parser.add_argument("-c", "--column", help="Specific column to display unique values for")
  
  args = parser.parse_args()
  
  try:
    df = load_csv(args.file)
    print(f"Loaded CSV with {len(df)} rows and {len(df.columns)} columns")
    print(f"Detected columns: {df.columns}")
    print("-----")
    print_unique_values(df, args.column)
    # hierarchy = analyze_vehicle_hierarchy(df)
    # print_vehicle_hierarchy(hierarchy)
  except Exception as e:
    print(f"Error: {e}")


if __name__ == "__main__":
  main()